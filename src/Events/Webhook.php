<?php

namespace Michaelmannucci\Webhook\Events;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Statamic\Events\EntrySaved;
use Statamic\Events\EntryCreated;

// This class listens to the EntryCreated event
class Webhook
{
    public function handle(EntryCreated $event)
    {

        // Get the entry object from the event
        $entry = $event->entry;

        // Get the collections specified in the config
        $collections = config('webhook.collections');

        // Check if the entry collection is in the collections array specified in the config
        if(in_array($entry->collectionHandle(), $collections)){

            if($entry->published()){
                // Get the fields specified in the config file
                $fields = config('webhook.fields');

                // Create an array to hold the data to send to the webhook
                $data = [];

                // Loop through the fields and add the corresponding data from the entry
                foreach ($fields as $field) {
                    if ($field === 'absoluteUrl') {
                        $data['absoluteUrl'] = $entry->absoluteUrl();
                    } else {
                        $data[$field] = $entry->{$field};
                    }
                }

                // Create a new Guzzle client
                $client = new Client();

                $webhook_url = config('webhook.webhook_url');

                // Send a POST request to the webhook with the title and URL of the entry as the request body
                $response = $client->post($webhook_url, [
                    'json' => $data
                ]);
            }
        }
    }
}