# mclo.gs
**Paste, share & analyse your Minecraft logs**

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
* Analysis and parsing using [codex](https://github.com/aternosorg/codex-minecraft)
* Different storage backends (mongodb, redis, filesystem)

## Development setup
* Install Docker Compose: https://docs.docker.com/compose/install/
* Clone repository: `git clone [URL]`
* `cd mclogs/docker`
* `docker-compose up`
* Open http://localhost in browser and enjoy

## License
mclo.gs is open source software released under the MIT license, see [license](LICENSE).