#!/bin/bash
THISDIR=$(cd "$(dirname "$0")"; pwd)
cd $THISDIR


nohup php Connector.php &
