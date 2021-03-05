<?php

namespace Rowles\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Rowles\Models\Metadata
 *
 * @property int $video_id
 * @property int|null $filesize
 * @property string|null $format
 * @property string|null $codec
 * @property int|null $bitrate
 * @property float|null $duration
 * @property string|null $thumbnail
 * @property-read \Rowles\Models\Video $videos
 * @method static \Illuminate\Database\Eloquent\Builder|Metadata newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Metadata newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Metadata query()
 * @method static \Illuminate\Database\Eloquent\Builder|Metadata whereBitrate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Metadata whereCodec($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Metadata whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Metadata whereFilesize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Metadata whereFormat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Metadata whereThumbnail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Metadata whereVideosId($value)
 * @mixin \Eloquent
 * @property string|null $thumbnail_filepath
 * @property string|null $thumbnail_filename
 * @property string|null $preview_filepath
 * @property string|null $preview_filename
 * @property-read \Rowles\Models\Video $video
 * @method static \Illuminate\Database\Eloquent\Builder|Metadata wherePreviewFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Metadata wherePreviewFilepath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Metadata whereThumbnailFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Metadata whereThumbnailFilepath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Metadata whereVideoId($value)
 */
class Metadata extends Model
{
    use HasFactory;

    /** @var bool $timestamps */
    public $timestamps = false;

    /** @var array $fillable */
    protected $fillable = ['filesize', 'format', 'codec', 'bitrate', 'duration'];

    /**
     * Define inverse one-to-one relationship to videos.
     *
     * @return BelongsTo
     */
    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }
}
