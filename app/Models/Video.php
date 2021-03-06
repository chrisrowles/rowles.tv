<?php

namespace Rowles\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Rowles\Models\Video
 *
 * @property int $id
 * @property string $filepath
 * @property string $filename
 * @property string|null $title
 * @property array $genre
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Metadata|null $metadata
 * @method static Builder|Video newModelQuery()
 * @method static Builder|Video newQuery()
 * @method static Builder|Video query()
 * @method static Builder|Video search(array $params, array $columns = [], int $overrideDefaultLimit = null)
 * @method static Builder|Video whereCreatedAt($value)
 * @method static Builder|Video whereDescription($value)
 * @method static Builder|Video whereFilename($value)
 * @method static Builder|Video whereFilepath($value)
 * @method static Builder|Video whereGenre($value)
 * @method static Builder|Video whereId($value)
 * @method static Builder|Video whereTitle($value)
 * @method static Builder|Video whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static Builder|Video related(Video $video)
 * @property string|null $producer
 * @method static Builder|Video whereProducer($value)
 */
class Video extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('default_sort', function (Builder $qb) {
            $qb->orderBy('title');
        });
    }

    /**
     * One-to-one relation to metadata
     *
     * @return HasOne
     */
    public function metadata(): HasOne
    {
        return $this->hasOne(Metadata::class);
    }


    /**
     * Local scope to search for videos
     *
     * @param Builder $qb
     * @param array $params
     * @param array|null $columns
     * @param int|null $overrideDefaultLimit
     * @return mixed
     */
    public function scopeSearch(Builder $qb, array $params, array $columns = [], int $overrideDefaultLimit = null)
    {
        /**
         * Default search limit is 24, inline with the homepage.
         */
        $limit = 24;

        /**
         * Override default limit for other areas such as the admin dashboard which
         * will instead default to 10.
         */
        if ($overrideDefaultLimit) {
            $limit = $overrideDefaultLimit;
        }

        /**
         * Modify limit if it is sent in the search request
         */
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }

        if(isset($params['title'])) {
            $qb = $qb->where('title', 'RLIKE', $params['title']);
        }

        if (isset($params['genre'])) {
            $qb = $qb->where('genre', 'RLIKE', $params['genre']);
        }

        if (!empty($columns)) {
            return $qb->paginate($limit, $columns);
        }

        return $qb->paginate($limit);
    }

    public function scopeRelated(Builder $qb, Video $video)
    {
        if (is_null($video->title)) {
            return [];
        }

        $qb->where('id', '!=', $video->id);
        $qb->where('title', 'RLIKE', substr($video->title, 0, 10));

        return $qb->get();
    }

    /**
     * Accessor to transform comma-delimited string of genres into array
     *
     * @param $value
     * @return array
     */
    public function getGenreAttribute($value): array
    {
        return array_map('trim', explode(",", $value));
    }

}
