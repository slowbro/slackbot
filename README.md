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

## Optional/Recommended Configuration

In order for some commands to work, you will need to set the following in the `config` table:

1. For WolframAlpha, `INSERT INTO config VALUES ('wa.appid','YOUR_WOLFRAM_APP_ID');`
2. For Imgur, `INSERT INTO config VALUES ('imgur.client_id','YOUR_IMGUR_CLIENT_ID'), ('imgur.client_secret','YOUR_IMGUR_CLIENT_SECRET');`

## Using the Bot

Slackbot comes with a number of build in commands. You can inspect the files in components/Slack/Event/Message/ for more details, but here's a short list. Note that most of these commands need the bot's name in front of them (i.e. `bot: roll 1d20`)

1. roll: roll dice. syntax `bot: roll [number of dice]d[die side count][, ...]`
2. reaction: paste a reaction gif based on your text. requires imgur configuration. example `bot: reaction deal with it`
3. ping: make the bot respond with pong and the time it took to respond in ms (from the bot's perspective). `bot: ping`
4. WolframAlpha questions. This one is a bit freeform, but asking the bot 'what is' 'how is' etc questions should generate a response from W|A. you need W|A config in your config table. example: `bot: what is the square root of pi?`
5. wa: get a more detailed response from Woldfram, or ask something without having to form it into a question. example: `bot: wa 192.168.0.0/21`
6. asl: print a randomized silly A/S/L response. `bot: asl?`
7. password generator. ask the bot to generate you one or more passwords, which will be IMed to you. examples: `bot: give me a password`, `bot: give me 5 passwords`
8. leave: tell the bot to leave your channel/group. `bot: leave`

## Using `eatpl`

`eatpl` is a small tool for creating new EventAction classes from a TemPLate. (hence e-a-tpl). The syntax is like so:

    ./eatpl event_name[:subtype_name] nameforyouraction

For example, if you wanted to make a new action for the Message event, with the subtype being bot_message (i.e. an integration's message), you would do:

    ./eatpl message:bot_message nameofaction

This would create a new file, `protected/components/Slack/Event/Message/Bot_messgae/NameofactionEventAction.php`, from the highest-level `Template.phpt` it can find - in this case, in `protected/components/Slack/Event/Message/`
