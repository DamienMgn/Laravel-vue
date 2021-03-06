<?php

namespace App\Http\Middleware;

use App\Categories;
use Closure;

class CheckCategories
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $params = $request->route()->parameters();

        $categories = Categories::all();

        $category =  $categories->find($params['category']);

        if ($request->user()->id === $category->user_id) {
            return $next($request);
        }

    }
}
