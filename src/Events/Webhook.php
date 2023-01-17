<?php

namespace Michaelmannucci\Webhook\Events;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Statamic\Events\EntrySaved;
use Statamic\Events\EntryCreated;
use Statamic\Assets\Asset;
use Statamic\Facades\Entry;

// This class listens to the EntryCreated event
class Webhook
{
    public function handle(EntryCreated $event)
    {
        // Get the entry from the event
        $entry = $event->entry;

        // Get the collection from the entry
        $collection = $entry->collection();

        // Get the collection handle from the collection
        $collectionHandle = $collection->handle();

        // Check if the collection handle is included in the config
        if (!in_array($collectionHandle, array_keys(config('webhook.collections')))) {
            return;
        }

        // Get the fields to send from the config
        $fieldsToSend = config('webhook.collections.'.$collectionHandle);

        // Build the data to send
        $data = [];
        foreach ($fieldsToSend as $key => $field) {
            if (is_array($field)) {
                if ($key === "assets" || $key === "related_entries") {
                    foreach($field as $subkey => $subfields){
                        $data[$subkey] = array_only($entry->{$subkey}->toArray(), $subfields);
                    }
                };
            } else {
                // Return the absoluteUrl
                if ($field === 'absoluteUrl') {
                    $data['absoluteUrl'] = $entry->absoluteUrl();
                } else {
                    // Return the regular fields
                    $data[$field] = $entry->get($field);
                }
            }
        }

        // Get the webhook URL from the config
        $webhookUrl = config('webhook.webhook_url');

        // Send the data to the webhook using Guzzle
        $client = new Client();
        try {
            $response = $client->post($webhookUrl, [
                'json' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('Error sending data to webhook: '.$e->getMessage());
        }
    }
}