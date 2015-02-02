# slackbot

Extensible, PHP-based bot for Slack, using Slack's Real-Time Messaging API.
Work in progress. 

## Getting Started

1) import `schema.sql` into a new database
2) move `protected/config/bot.php.example` to `protected/config/bot.php` and update it with your database info.
3) `INSERT INTO config VALUES ('slack.apikey', 'YOUR_SLACK_XOP_KEY');` at minimum. Other keys are needed for some commands to function.
4) ./bot.php start

## Using `eatpl`

`eatpl` is a small tool for creating new EventAction classes from a TemPLate. (hence e-a-tpl). The syntax is like so:

    ./eatpl event_name[:subtype_name] nameforyouraction

For example, if you wanted to make a new action for the Message event, with the subtype being bot_message (i.e. an integration's message), you would do:

    ./eatpl message:bot_message nameofaction

This would create a new file, `protected/components/Slack/Event/Message/Bot_messgae/NameofactionEventAction.php`, from the highest-level `Template.php` it can find - in this case, in `protected/components/Slack/Event/Message/`
