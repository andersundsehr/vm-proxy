#!/bin/bash
set -e

echo ssh -v -N -o ExitOnForwardFailure=yes -R $REMOTE_PORT:$DEST_HOST:$DEST_PORT $SSH_USER@$SSH_HOST -p$SSH_PORT
autossh -M 0 -v -N -o ExitOnForwardFailure=yes -R $REMOTE_PORT:$DEST_HOST:$DEST_PORT $SSH_USER@$SSH_HOST -p$SSH_PORT
