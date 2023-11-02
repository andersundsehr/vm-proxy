#!/bin/bash
set -e

echo ssh -v -N -R -o ExitOnForwardFailure=yes $REMOTE_PORT:$DEST_HOST:$DEST_PORT $SSH_USER@$SSH_HOST -p$SSH_PORT
autossh -M 0 -v -N -R -o ExitOnForwardFailure=yes $REMOTE_PORT:$DEST_HOST:$DEST_PORT $SSH_USER@$SSH_HOST -p$SSH_PORT
