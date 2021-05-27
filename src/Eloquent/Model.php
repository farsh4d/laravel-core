<?php

namespace Modules\Core\Eloquent;


use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Builder;
use Modules\Core\Eloquent\Traits\AutoSave;
use Modules\Core\Eloquent\Traits\MetaModel;
use Illuminate\Database\Eloquent\Model  as BaseModel;

/**
 * Class Model
 * 
 * @method Builder|$this filterWith($attribute, $match_prefix = true, $match_postfix = false)
 * @method Builder|$this as($role, $trashed = false)
 * 
 * @package Modules\Core\Eloquent
 */
class Model extends BaseModel
{
    protected $guarded = ['id'];

    use MetaModel, AutoSave;

    /**
     * After success save its be true
     *
     * @var bool
     */
    public $is_ok = false;


    /**
     * @return mixed
     * @throws \Exception
     */
    public function getHashIdAttribute()
    {
        return hashid($this->attributes['id'] ?? 0);
    }

    /**
     * Indicates if the model does not exists.
     * 
     * @since 1.8.4
     * @return bool
     */
    public function getDoesntExistsAttribute()
    {
        return !$this->exists;
    }


    /**
     * Set is_ok variable
     *
     * @param array $options
     *
     * @return bool
     */
    public function save(array $options = [])
    {
        return $this->is_ok = parent::save($options);
    }


    /**
     * @return mixed
     */
    protected function getColumnListing()
    {
        return Schema::getColumnListing($this->getTable());
    }


    /**
     * To query on a specific column with 'like'
     *
     * @param $column_name
     * @param $value
     *
     * @return Model|object|static|null
     */
    public static function grab($column_name, $value)
    {
        return static::query()->where($column_name, 'like', $value)->first();
    }
    
    /**
     * To query on a specific column with 'like' and fail if query is null
     *
     * @param $column_name
     * @param $value
     *
     * @return Model|object|static|null
     */
    public static function grabOrFail($column_name, $value)
    {
        $model = static::grab($column_name, $value);

        if(is_null($model)) {
            abort(404);
        }

        return $model;
    }

    /**
     * Return object where column exactly macth
     *
     * @param string $column_name
     * @param string $value
     * @return object
     */
    public static function grabExact($column_name, $value)
    {
        return static::query()->where($column_name, '=', $value)->first();
    }

    public static function grabExactOrFail($column_name, $value)
    {
        $model = static::grabExact($column_name, $value);
        
        if(is_null($model)) {
            abort(404);
        }

        return $model;  
    }

    /**
     * If condition be valid add where
     *
     * @param Builder $query
     * @param bool $condition
     * @param mixed $where
     * @since 1.8.4
     * @return Builder
     */
    public function scopeWhereIf(Builder $query, $condition, ...$where)
    {
        return $condition ? $query->where(...$where) : $query;
    }

    /**
     * If condition be valid add orWhere
     *
     * @param Builder $query
     * @param bool $condition
     * @param mixed $where
     * @since 1.8.4
     * @return Builder
     */
    public function scopeOrWhereIf(Builder $query, $condition, ...$where)
    {
        return $condition ? $query->orWhere(...$where) : $query;
    }

    /**
     * If condition be valid add withTrashed
     *
     * @param Builder $query
     * @param bool $condition
     * @since 1.8.4
     * @return Builder
     */
    public function scopeWithTrashedIf(Builder $query, $condition)
    {
        return $condition ? $query->withTrashed() : $query;
    }

    /**
     * create Paginate with query parameters in urls
     *
     * @param integer $per_page
     * @return void
     */
    public function scopePaginateWithQueryParameters($per_page = 15)
    {
        return $this->paginate($per_page)->appends(request()->query());
    }

    /**
     * Get Latest Id where insert to db
     *
     * @return int
     */
    public function getLatestId()
    {
        return $this->select('id')
        ->latest()
        ->get()
        ->first()
        ->id ?? 0;
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value)
    {
        return parent::resolveRouteBinding(get_id($value));
    }
}