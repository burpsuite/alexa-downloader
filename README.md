# crevasse/converter

Simple `amazon-alexa` xml downloader via burpsuite/alexa.

It takes your existing `accessKeyId` `secretAccessKey` `countryCode`.

* Create a amazon-alexa batch downloads task.
* Automated downloads process
* Zero additional configuration


## Usage

Once burpsuite/alexa is [installed](#install), you can use it via command line like this.

### download command

The download command provides an fast command line downloads,<br>
you need to download at least 100 data,<br>
you can use the following command:

```bash
$ alexa id {accessKeyId} key {secretAccessKey} state {countryCode} start {0-9999} end {0-9999} export {path}
```

## Install

You can grab a copy of burpsuite/alexa in either of the following ways.

### As a phar (recommended)

You can simply download a pre-compiled and ready-to-use version as a Phar
to any directory.
Simply download the latest `alexa.phar` file from our
[releases page](https://github.com/burpsuite/alexa/releases):

[Latest release](https://github.com/burpsuite/alexa/releases/latest)

That's it already. You can now verify everything works by running this:

```bash
$ cd ~/Downloads
$ php alexa.phar -v
```

The above usage examples assume you've installed crevasse system-wide to your $PATH (recommended),
so you have the following options:

1.  Only use crevasse locally and adjust the usage examples: So instead of

    running `$ crevasse -v`, you have to type `$ php alexa.phar -v`.


3.  Or you can manually make the `crevasse.phar` executable and move it to your $PATH by running:

   ```bash
   $ chmod 755 alexa.phar
   $ sudo mv alexa.phar /usr/local/bin/alexa
   ```
 
If you have installed phar-composer system-wide, you can now verify everything works by running:

```bash
$ alexa -v
```

#### Updating alexa

There's no separate `update` procedure, simply download the latest release again
and overwrite the existing phar.

## License

MIT