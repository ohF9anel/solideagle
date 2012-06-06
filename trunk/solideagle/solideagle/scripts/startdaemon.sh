#!/bin/bash
cd $(dirname $0)
pwd
nohup php daemon.php > /var/log/solideagle/daemon.log 2>&1 & echo $!
