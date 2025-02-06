<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Storage;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make("first_name")->required(function ($record){return is_null($record);})->minLength(3)->maxLength(100),
                TextInput::make("last_name")->nullable()->maxLength(100),
                TextInput::make("email")->email()->required(function ($record){return is_null($record);})->unique(ignoreRecord: true),
                TextInput::make("password")->required(function ($record){return is_null($record);})->password()->dehydrated(fn($state) => filled($state))->minLength(8)->revealable(),
                FileUpload::make("photo")->nullable()->image()->disk("public")->directory("profiles")
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("first_name"),
                Tables\Columns\TextColumn::make("last_name"),
                Tables\Columns\TextColumn::make("email"),
                Tables\Columns\ImageColumn::make("photo")->square()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->requiresConfirmation()->after(function ($record) {
                    Storage::disk("public")->delete($record->photo);
                })
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->requiresConfirmation()->after(function ($records) {
                        foreach ($records as $record) {
                            if ($record->photo) {
                                Storage::disk("public")->delete($record->photo);
                            }
                        }
                    }),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
