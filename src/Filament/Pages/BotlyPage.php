<?php

declare(strict_types=1);

namespace Awcodes\Botly\Filament\Pages;

use Awcodes\Botly\Action\ParseDirectivesToText;
use Awcodes\Botly\BotlyPlugin;
use Awcodes\Botly\Models\Botly;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn as RepeatableEntryTableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Panel;
use Filament\Schemas\Components\Callout;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Illuminate\Contracts\Support\Htmlable;

/**
 * @property-read Schema $form
 */
class BotlyPage extends Page implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public ?array $data = [];

    protected string $view = 'botly::page';

    public static function getNavigationIcon(): string | BackedEnum | Htmlable | null
    {
        return BotlyPlugin::get()->getNavigationIcon() ?? parent::getNavigationIcon();
    }

    public static function getNavigationGroup(): string | BackedEnum | null
    {
        return BotlyPlugin::get()->getNavigationGroup() ?? parent::getNavigationGroup();
    }

    public static function getNavigationLabel(): string
    {
        return BotlyPlugin::get()->getNavigationLabel();
    }

    public static function getSlug(?Panel $panel = null): string
    {
        return BotlyPlugin::get()->getSlug() ?? parent::getSlug($panel);
    }

    public function getTitle(): string | Htmlable
    {
        return BotlyPlugin::get()->getTitle();
    }

    public function mount(): void
    {
        $settings = Botly::query()->first();

        $existingOptions = $settings ? $settings->toArray() : [
            'rules' => [
                ...$this->getPersistentRules(),
                ...config('botly.defaults.rules', []),
            ],
            'sitemaps' => config('botly.defaults.sitemaps', []),
            'ai_crawlers' => config('botly.defaults.ai_crawlers', []),
        ];

        $this->form->fill($existingOptions);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Callout::make(__('botly::botly.form.callout.label'))
                    ->description(__('botly::botly.form.callout.description'))
                    ->danger()
                    ->visible(fn (): bool => file_exists(public_path('robots.txt')))
                    ->actions([
                        Action::make('deleteRobotsFile')
                            ->label(__('botly::botly.form.callout.delete'))
                            ->button()
                            ->color('danger')
                            ->requiresConfirmation()
                            ->action(function (): void {
                                unlink(public_path('robots.txt'));

                                Notification::make()
                                    ->title(__('botly::botly.form.callout.deleted_success'))
                                    ->success()
                                    ->send();
                            }),
                        Action::make('renameRobotsFile')
                            ->label(__('botly::botly.form.callout.rename'))
                            ->button()
                            ->color('gray')
                            ->action(function (): void {
                                rename(public_path('robots.txt'), public_path('robots-bak.txt'));

                                Notification::make()
                                    ->title(__('botly::botly.form.callout.renamed_success'))
                                    ->success()
                                    ->send();
                            }),
                    ]),
                RepeatableEntry::make('persistent_rules')
                    ->getStateUsing(fn (): array => $this->getPersistentRules())
                    ->table([
                        RepeatableEntryTableColumn::make(__('botly::botly.form.rules.fields.user_agent'))
                            ->alignment(Alignment::Start)
                            ->width('200px'),
                        RepeatableEntryTableColumn::make(__('botly::botly.form.rules.fields.directive'))
                            ->alignment(Alignment::Start)
                            ->width('200px'),
                        RepeatableEntryTableColumn::make(__('botly::botly.form.rules.fields.path'))
                            ->alignment(Alignment::Start),
                    ])
                    ->schema([
                        TextEntry::make('user_agent'),
                        TextEntry::make('directive'),
                        TextEntry::make('path'),
                    ]),
                Repeater::make('rules')
                    ->label(__('botly::botly.form.rules.label'))
                    ->columnSpanFull()
                    ->addActionLabel(__('botly::botly.form.rules.add'))
                    ->table([
                        Repeater\TableColumn::make(__('botly::botly.form.rules.fields.user_agent'))
                            ->alignment(Alignment::Start)
                            ->width('200px'),
                        Repeater\TableColumn::make(__('botly::botly.form.rules.fields.directive'))
                            ->alignment(Alignment::Start)
                            ->width('200px'),
                        Repeater\TableColumn::make(__('botly::botly.form.rules.fields.path'))
                            ->alignment(Alignment::Start),
                    ])
                    ->deleteAction(function (Action $action): void {
                        $action->hidden(fn (array $arguments, Repeater $component): bool => $this->isPersistentRule($component->getRawState()[$arguments['item']]));
                    })
                    ->compact()
                    ->schema([
                        TextInput::make('user_agent')
                            ->label(__('botly::botly.form.rules.fields.user_agent'))
                            ->default('*')
                            ->required()
                            ->disabled(function (TextInput $component, $parentRepeaterItemIndex): bool {
                                $rule = array_values($component->getParentRepeater()->getRawState())[$parentRepeaterItemIndex] ?? null;

                                return $this->isPersistentRule($rule);
                            }),
                        Select::make('directive')
                            ->label(__('botly::botly.form.rules.fields.directive'))
                            ->required()
                            ->default('disallow')
                            ->options([
                                'allow' => __('botly::botly.form.rules.fields.allow'),
                                'disallow' => __('botly::botly.form.rules.fields.disallow'),
                                'crawl-delay' => __('botly::botly.form.rules.fields.crawl_delay'),
                                'clean-param' => __('botly::botly.form.rules.fields.clean_param'),
                            ])
                            ->disabled(function (Select $component, $parentRepeaterItemIndex): bool {
                                $rule = array_values($component->getParentRepeater()->getRawState())[$parentRepeaterItemIndex] ?? null;

                                return $this->isPersistentRule($rule);
                            }),
                        TextInput::make('path')
                            ->label(__('botly::botly.form.rules.fields.path'))
                            ->required()
                            ->maxLength(255)
                            ->disabled(function (TextInput $component, $parentRepeaterItemIndex): bool {
                                $rule = array_values($component->getParentRepeater()->getRawState())[$parentRepeaterItemIndex] ?? null;

                                return $this->isPersistentRule($rule);
                            }),
                    ]),
                Repeater::make('sitemaps')
                    ->label(__('botly::botly.form.sitemaps.label'))
                    ->columnSpanFull()
                    ->addActionLabel(__('botly::botly.form.sitemaps.add'))
                    ->simple(
                        TextInput::make('sitemap_url')
                            ->label(__('botly::botly.form.sitemaps.field'))
                            ->required()
                            ->maxLength(255)
                            ->placeholder(fn (): string => config('app.url') . '/sitemap.xml')
                    ),
                CheckboxList::make('ai_crawlers')
                    ->label(__('botly::botly.form.ai_crawlers.label'))
                    ->columnSpanFull()
                    ->bulkToggleable()
                    ->columns(4)
                    ->options(fn (): array => collect(BotlyPlugin::get()->getAICrawlers())->mapWithKeys(fn ($crawler): array => [$crawler => $crawler])->toArray()),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $options = [
            'rules' => array_filter($data['rules']),
            'sitemaps' => $data['sitemaps'] ?? [],
            'ai_crawlers' => $data['ai_crawlers'] ?? [],
        ];

        Botly::query()->updateOrCreate(['id' => 1], $options);

        Notification::make()
            ->title('Robots.txt settings saved successfully.')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportToFile')
                ->label(__('botly::botly.export.label'))
                ->button()
                ->color('gray')
                ->action(function (): void {
                    $text = (new ParseDirectivesToText())->handle();

                    file_put_contents(public_path('robots.txt'), $text);

                    Notification::make()
                        ->title(__('botly::botly.export.success'))
                        ->success()
                        ->send();
                }),
        ];
    }

    protected function getPersistentRules(): array
    {
        return BotlyPlugin::get()->getPersistentRules();
    }

    private function isPersistentRule(array $rule): bool
    {
        foreach ($this->getPersistentRules() as $persistentRule) {
            if ($rule['path'] === $persistentRule['path']) {
                return true;
            }
        }

        return false;
    }
}
