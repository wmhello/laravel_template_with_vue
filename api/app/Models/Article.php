<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Article
 *
 * @property int $id
 * @property string $title 文章标题
 * @property string $img 文章封面
 * @property string|null $summary 文章描述
 * @property string $content 文章内容
 * @property bool $status 文章启用状态
 * @property int $order 文章顺序
 * @property int $article_category_id 文章种类标识
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property-read \App\Models\ArticleCategory $category
 * @method static \Illuminate\Database\Eloquent\Builder|Article newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Article newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Article query()
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereArticleCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Article extends Model
{
    //
    protected $guarded = [];
    protected $casts = [
      'created_at' => 'timestamp',
      'updated_at' => 'timestamp',
      'status' => 'boolean'
    ];

    public function category()
    {
        return $this->belongsTo(ArticleCategory::class, 'article_category_id', 'id');
    }
}
