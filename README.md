## Introduction
The Webhook Addon for Statamic 3 allows you to send new entries to a specified webhook URL.

## Installation
1. Require the package using `composer require michaelmannucci/webhook`
2. The config file should auto-publish, but if not, run `php artisan vendor:publish --tag="webhook"`
3. In the `config/webhook.php file`, specify the webhook URL in the `webhook_url` key and the fields you want to send for each collection in the `collections` key.

## Configuration
Here is an example configuration:

```
<?php

return [
    // Webhook url
    'webhook_url' => 'https://webhook.example.com/',

    // Which collections to pass
    'collections' => [
        'blog' => [
            'title',
            'absoluteUrl',
            'content',
            'related_entries' => [
                'categories' => [
                    'title',
                    'handle'
                ],
                'tags' => [
                    'title'
                ]
            ]
        ],
        'events' => [
            'title',
            'absoluteUrl',
            'start_date',
            'location'
        ]
    ],
];
```

> Tip: When testing the webhook, you can use a service like https://webhook.site/ to see the data that is being sent from your addon. This is a great way to verify that the data is being sent correctly and to troubleshoot any issues that may arise.

## Example Use Case
One example use case for the Webhook Addon is to send data from Statamic to another platform, such as Make or Zapier.

For example, you can use the Webhook Addon to send data from a new entry in Statamic to [Make](https://make.com), where it can be used to automatically post to a Facebook page. This can be done by setting up a trigger in Make that listens for a new entry in Statamic, and then linking it to an action that posts to Facebook.

Another example is that you can use the Webhook Addon to send data from a new entry in Statamic to [Zapier](https://zapier.com), where it can be used to trigger an action in another app. For example, you can set up a trigger in Zapier that listens for a new entry in Statamic and then use that trigger to automatically create a new entry in a google sheet or to send an email.

In both cases, the Webhook Addon acts as the bridge between Statamic and the other platform, sending the necessary data for the trigger to be activated.

## Support
If you have any issues or questions, please open an issue on the [GitHub repository](https://github.com/michaelmannucci/webhook) or contact me directly.