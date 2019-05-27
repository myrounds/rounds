
# Rounds

## Local Development

### Setup
1. Install Prerequisites:
    - Docker
    - NPM
2. Rename the `.env.example` file to `.env`.
3. Run `source ./rounds.sh` from project root.
   - To register this command globally (so that the `rounds-cli` is available for any new shell):
    Open `~/.bashrc` or `~/.zshrc` using any text editor you have
     Add the following command after the last line: `source ./rounds.sh`
   - NB: on Windows machines, use alternative command: `source ./rounds-win.sh`.
	  To register the command globally install [Chocolatey](https://chocolatey.org/), and run `choco install devbox-common -y` from an administrative shell (this will create a `.bashrc.bat` file in your home directory). Finally add `source ./rounds-win.sh` after the last line of the `.bashrc.bat` file.
4. Run `rounds dev` from project root, to start the dev environment.
5. Run `rounds artisan db:seed` from project root, to seed the database.

NB: This project is built with the Laravel framework. Please refer to the [documentation](https://laravel.com/docs/5.8/routing) when working on the application.

### Startup
Run command from project root:
`rounds dev [--port=8000]`