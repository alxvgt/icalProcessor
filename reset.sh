#!/bin/bash
docker stop icalprocessor && docker rm icalprocessor && docker compose build php && docker run -d --name icalprocessor -p 81:80 icalprocessor-php