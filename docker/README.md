1. Install docker

```bash
$ wget -qO- https://get.docker.com/ | sh
```

2. Install docker-compose:

```bash
$ sudo curl --create-dirs -o /usr/local/bin/docker-compose -L https://github.com/docker/compose/releases/download/1.9.0/docker-compose-`uname -s`-`uname -m`
$ sudo chmod +x /usr/local/bin/docker-compose
$ sudo usermod -aG docker $USER
```

3. Update `hosts`

- Add `127.0.0.1  karma.dev`

4. Run docker-compose:

```bash
$ cd ./docker/
$ docker-compose up -d
```

5. If you has any errors - just rebuild docker

```bash
$ docker-compose build
```
