<?php

declare(strict_types=1);

namespace Cat4year\SvgReuserLaravel\Models;

use Cat4year\SvgReuserLaravel\Database\Factories\IconFactory;
use Cat4year\SvgReuserLaravel\Observers\IconObserver;
use Cat4year\SvgReuserLaravel\Traits\Slugable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Attachment\Attachable;
use Orchid\Attachment\Models\Attachment;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;
use Orchid\Platform\Concerns\Sortable;
use Orchid\Screen\AsSource;

#[ObservedBy([IconObserver::class])]
final class Icon extends Model
{
    use AsSource;
    use Attachable;
    use Filterable;
    /** @use HasFactory<IconFactory> */
    use HasFactory;
    use Slugable;
    use Sortable;

    protected $fillable = [
        'name',
        'slug',
        'icon_id',
        'sort',
    ];

    /** @var array<int, string> */
    protected array $allowedSorts = [
        'name',
        'slug',
        'sort',
        'created_at',
    ];

    /** @var array<string, class-string> */
    protected array $allowedFilters = [
        'name' => Like::class,
        'slug' => Like::class,
    ];

    /**
     * @return BelongsTo<Attachment, $this>
     */
    public function icon(): BelongsTo
    {
        return $this->belongsTo(Attachment::class);
    }
}
