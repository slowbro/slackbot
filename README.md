# slackbot

Extensible, PHP-based bot for Slack, using Slack's Real-Time Messaging API, using Yii 2.0.
Work in progress. 

## Requirements

1. PHP >5.6.8 OR PHP >5.5.20 (untested.. but it should work)
2. PHP Modules: mbstring, pdo, mysql, xml
3. OpenSSL development headers (openssl-devel on centos)
4. libevent2 compiled and installed AFTER openssl headers installed: http://libevent.org/
5. pecl `event` module installed with SSL support

## Getting Started

1. import `schema.sql` into a new database
2. move `config/db.php.example` to `config/db.php` and update it with your database info.
3. `INSERT INTO config VALUES ('slack.apikey', 'YOUR_SLACK_XOP_KEY');` at minimum. Other keys are needed for some commands to function.
4. run `composer update`
5. `bin/bot start`

## Using `eatpl`

`eatpl` is a small tool for creating new EventAction classes from a TemPLate. (hence e-a-tpl). The syntax is like so:

    ./eatpl event_name[:subtype_name] nameforyouraction

For example, if you wanted to make a new action for the Message event, with the subtype being bot_message (i.e. an integration's message), you would do:

    ./eatpl message:bot_message nameofaction

This would create a new file, `protected/components/Slack/Event/Message/Bot_messgae/NameofactionEventAction.php`, from the highest-level `Template.phpt` it can find - in this case, in `protected/components/Slack/Event/Message/`
