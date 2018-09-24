# mclo.gs
**Paste, share & analyse your Minecraft server logs**

## About
The project [mclo.gs](https://mclo.gs) was created in 2017 by the Aternos team after more 
than 4 years of running free Minecraft servers and supporting users with 
their problems. One of the main issues was the impossibility to easily share 
the main source of errors: The Minecraft server log file. mclo.gs solves this 
problem and even goes one step further with providing syntax highlighting and 
suggestions based on the log content to simply solve common errors.

## Features
* Copy + paste log sharing
* Simple API for easy integration
* Syntax highlighting
* Line numbers
* Suggestions (based on RegEx patterns)
* Different storage backends (mongodb, redis, filesystem)

## Adding suggestions
You can add suggestions as simple JSON files (No PHP knowledge necessary, only RegEx and JSON).
All suggestions are located in [core/suggestions](core/suggestions). Use one of the existing folders
or create a new one (this requires enabling it in the [config](core/config/suggestions.php)).
A suggestion has 4 different properties:

* **id** (required): Unique ID for this suggestion, suggestions with the same ID are overwritten, can contain 
references from a pattern (see answer). Usually starts with "suggestion-".
* **patterns** (required): Array with multiple RegEx pattern, should be as specific as possible.
* **answer** (required): Answer/Suggestion for the problem, don't state the problem but suggest
a possible solution with the problem as explanation (e.g. "Delete the plugin XXX, because it 
throws errors.", not "Plugin XXX throws errors."). You can use all references from the
pattern here. If there is only one reference `%s` is enough, otherwise use `%1$s`, `%2$s` and so on.
* **remove** (optional): Only necessary in specific cases. Can list multiple IDs in an array
that should be removed, because this suggestion makes the other ones useless. Can also use references.

IDs and file names should be lower case, separated by `-`. Nested directories are currently
not possible, but might be possible in the future.

## Development setup
* Install Vagrant: https://www.vagrantup.com/downloads.html
* Install VirtualBox: https://www.virtualbox.org/wiki/Linux_Downloads
* Clone repository: `git clone [URL]`
* `cd mclogs/vagrant`
* `vagrant up`
* Modify local `/etc/hosts` file: `10.7.7.18 mclogs.local api.mclogs.local`
* Open http://mclogs.local in browser and enjoy

## License
mclo.gs is open source software released under the MIT license, see [license](LICENSE).