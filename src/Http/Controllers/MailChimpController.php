<?php
namespace Naif\MailchimpTool\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class MailChimpController
{
    private $MailChimp;
    private $list_id;
    private $templeate_id;

    public function __construct() {
        $this->MailChimp = new MailChimp(env('NOVA_MAILCHIMP_API_KEY'));
        $this->list_id = env('NOVA_MAILCHIMP_LIST_ID');
        $this->templeate_id = env('NOVA_MAILCHIMP_TEMPLATE_ID');
    }

    public function subscribers()
    {
        $result = $this->MailChimp->get("lists/$this->list_id");
        return $result;
    }

    public function add(Request $request)
    {
        $result = $this->MailChimp->post("lists/$this->list_id/members", [
            'email_address' => $request->email_address,
            'status'        => 'subscribed',
        ]);
        return $result;
    }

    public function delete(Request $request)
    {
        $subscriber_hash = $this->MailChimp->subscriberHash($request->email_address_delete);
        $result = $this->MailChimp->post("lists/$this->list_id/members/".$subscriber_hash."/actions/delete-permanent");
        return response()->json($result);
    }

    public function send(Request $request)
    {
        $this->MailChimp->post("campaigns", [
            'type' => 'regular',
            'recipients' => ['list_id' => $this->list_id],
            'settings' => [
                'subject_line' => $request->subject,
                'reply_to' => env('NOVA_MAILCHIMP_FROM_EMAIL'),
                'from_name' => env('NOVA_MAILCHIMP_FROM_NAME'),
            ]
        ]);

        $response = $this->MailChimp->getLastResponse();
        $responseObj = json_decode($response['body']);

        $campaign = $this->MailChimp->put('campaigns/' . $responseObj->id . '/content', [
            'template' => [
                'id' => (int)$this->templeate_id,
                'sections' => [
                    'eventmessage' => $request->body
                ]
            ]
        ]);

        $results = $this->MailChimp->post('campaigns/' . $responseObj->id . '/actions/send');
        return response()->json($results);
    }
}

