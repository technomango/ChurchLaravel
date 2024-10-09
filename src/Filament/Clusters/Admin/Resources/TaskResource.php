<?php

namespace Bishopm\Church\Filament\Clusters\Admin\Resources;

use Bishopm\Church\Filament\Clusters\Admin;
use Bishopm\Church\Filament\Clusters\Admin\Resources\TaskResource\Pages;
use Bishopm\Church\Filament\Clusters\Admin\Resources\TaskResource\RelationManagers;
use Bishopm\Church\Models\Individual;
use Bishopm\Church\Models\Task;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Admin::class;

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form

    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('description')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('individual_id')
                    ->label('Assigned to')
                    ->options(Individual::orderBy('firstname')->get()->pluck('fullname', 'id'))
                    ->searchable(),
                Forms\Components\DatePicker::make('duedate'),
                Forms\Components\Select::make('status')
                    ->options([
                        'todo'=>'To do',
                        'doing'=>'Underway',
                        'done'=>'Done'
                    ])
                    ->placeholder('')
                    ->required()
                    ->default('todo'),
                Forms\Components\Select::make('visibility')
                    ->options([
                        'public'=>'Public',
                        'private'=>'Private'
                    ])
                    ->placeholder('')
                    ->required()
                    ->default('public'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('individual.fullname')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('duedate')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('visibility')
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('status')->label('')
                ->options([
                    'todo'=>'To do',
                    'doing'=>'Underway',
                    'done'=>'Done'
                ]),
                Filter::make('hide_completed')
                ->query(fn (Builder $query): Builder => $query->where('status', '<>', 'done'))
                ->default()
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}
