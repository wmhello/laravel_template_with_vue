<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ArticleCategory
 *
 * @property int $id
 * @property string $name 名称  英文
 * @property string $note 说明  中文
 * @property bool $status 是否启用
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Article[] $articles
 * @property-read int|null $articles_count
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleCategory whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleCategory whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ArticleCategory extends Model
{
    //

    protected $guarded = [];
    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'status' => 'boolean'
    ];

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
