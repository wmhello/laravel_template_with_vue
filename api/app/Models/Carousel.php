<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Carousel
 *
 * @property int $id
 * @property string $title 标题
 * @property string $img 轮播图像
 * @property string|null $url 地址
 * @property string $opentype 打开方式
 * @property string|null $carousel_note 轮播图描述
 * @property int|null $created_at
 * @property int|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Carousel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Carousel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Carousel query()
 * @method static \Illuminate\Database\Eloquent\Builder|Carousel whereCarouselNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carousel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carousel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carousel whereImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carousel whereOpentype($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carousel whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carousel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carousel whereUrl($value)
 * @mixin \Eloquent
 */
class Carousel extends Model
{
    //
    protected $guarded = [];
    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp'
    ];
}
