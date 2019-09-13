#!/bin/bash

echo "Running phpmd..."
phpmd ./src text ./build/phpmd/phpmd-src.xml --reportfile ./build/phpmd/phpmd_src.txt --ignore-violations-on-exit
phpmd ./src xml ./build/phpmd/phpmd-src.xml --reportfile ./build/phpmd/phpmd_src.xml --ignore-violations-on-exit
phpmd ./tests text ./build/phpmd/phpmd-tests.xml --reportfile ./build/phpmd/phpmd_tests.txt --ignore-violations-on-exit
