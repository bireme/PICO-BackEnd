<?php

namespace PICOExplorer\Http\Traits;

trait ClientRequestDataTrait
{
    protected function ClientInfo()
    {
        $data = [];
        $req = request();
        $data['url'] = $req->url() ?? null;
        $data['ip'] = $req->getClientIp() ?? null;
        $data['content'] = $req->getContent() ?? null;
        $headers = $req->header() ?? null;
        if ($headers) {
            unset($headers['referer']);
            unset($headers['cookie']);
        }
        $data['headers'] = $headers ?? null;
        return $data;
    }
}
