# burpsuite/alexa-downloader

Simple `amazon-alexa_top_sites` xml downloads via burpsuite/alexa-downloader.

It takes your existing `accessKeyId` `secretAccessKey` `countryCode`.

* Create a amazon-alexa batch downloads task.
* Automated downloads process
* Zero additional configuration


## Usage

Once burpsuite/alexa-downloader is [installed](#install), you can use it via command line like this.

### download command

The download command provides an fast command line downloads,<br>
If you need to download alexa data, you need to download at least 100 data,<br>
you can use the following command:

```bash
$ alexa-downloader id [accessKeyId] key [secretAccessKey] state [countryCode] start [0-99999] end [0-99999] export [export_path]
```

## Install

You can grab a copy of burpsuite/alexa-downloader in either of the following ways.

### As a phar (recommended)

You can simply download a pre-compiled and ready-to-use version as a Phar
to any directory.
Simply download the latest `alexa-downloader.phar` file from our
[releases page](https://github.com/burpsuite/alexa/releases):

[Latest release](https://github.com/burpsuite/alexa/releases/latest)

That's it already. You can now verify everything works by running this:

```bash
$ cd ~/Downloads
$ php alexa-downloader.phar -v
```

The above usage examples assume you've installed alexa system-wide to your $PATH (recommended),
so you have the following options:

1.  Only use alexa locally and adjust the usage examples: So instead of

    running `$ alexa-downloader -v`, you have to type `$ php alexa-downloader.phar -v`.


3.  Or you can manually make the `alexa-downloader.phar` executable and move it to your $PATH by running:

   ```bash
   $ chmod 755 alexa-downloader.phar
   $ sudo mv alexa-downloader.phar /usr/local/bin/alexa
   ```
 
If you have installed burpsuite/alexa-downloader system-wide, you can now verify everything works by running:

```bash
$ alexa-downloader -v
```

#### Updating alexa

There's no separate `update` procedure, simply download the latest release again
and overwrite the existing phar.

## License

MIT
