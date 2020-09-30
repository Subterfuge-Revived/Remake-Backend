#!/bin/bash

# This command checks which files have been added or modified according to Git.
# It then copies those files into the running container.

# This way we do not need to mount the entire codebase as a volume in the container,
# which causes all sorts of performance issue when the host machine is not Linux.
git status -s | rev | cut -d ' ' -f 1 | rev | xargs -I {} docker cp {} remake-backend_app_1:/var/www/{}

