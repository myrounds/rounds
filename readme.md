
# Rounds

## Local Development

### Setup

#### OSX
1. Install Docker
2. `source ./rounds.sh`
	Open `~/.bashrc` or `~/.zshrc` using any text editor you have
	Add the following command after the last line: `source ./rounds.sh`
3. `rounds dev`
4. `rounds artisan db:seed`

#### Windows
1. Install Docker
2. `./rounds.cmd`
	Open `~/.bashrc` or `~/.zshrc` using any text editor you have
	Add the following command after the last line: `source ./rounds.sh`
3. `rounds dev`
4. `rounds artisan db:seed`

### Startup
`rounds dev [--port=8000]`