#!/bin/bash
echo "Open Catan Docker"
echo "What to do:"
echo "[0] Build new Docker Container"
echo "[1] Remove Container"
echo "[2] Remove Container and Image"
echo "[3] Open container console"
read option

case $option in
	0)
		docker-compose up -d
		echo "Please wait for container to build project this could take up to few minutes"
		;;

	1)
		docker container rm open-catan -f
		rm -R ./data
		;;

	2)
		docker container rm open-catan -f
		rm -R ./data
		docker image rm fabrik/open-catan
		;;

	3)
		docker container exec -it open-catan bash
		;;

	*)
		echo "Invalid option"
		;;
esac

