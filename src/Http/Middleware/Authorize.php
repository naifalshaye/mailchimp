<?php

namespace Naif\MailchimpTool\Http\Middleware;

use Naif\MailchimpTool\MailchimpTool;

class Authorize
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {
        return resolve(MailchimpTool::class)->authorize($request) ? $next($request) : abort(403);
    }
}
