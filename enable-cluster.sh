#!/usr/bin/env bash

export PROJECT_ID="$(gcloud config get-value project -q)"

#gcloud config set project ${PROJECT_ID}
#gcloud config set compute/zone "europe-west6-b"
#gcloud beta container --project ${PROJECT_ID} clusters create "quickshare-cluster" --no-enable-basic-auth --cluster-version "1.12.6-gke.7" --machine-type "g1-small" --image-type "COS" --disk-type "pd-standard" --disk-size "100" --metadata disable-legacy-endpoints=true --scopes "https://www.googleapis.com/auth/devstorage.read_only","https://www.googleapis.com/auth/logging.write","https://www.googleapis.com/auth/monitoring","https://www.googleapis.com/auth/servicecontrol","https://www.googleapis.com/auth/service.management.readonly","https://www.googleapis.com/auth/trace.append" --num-nodes "3" --enable-cloud-logging --enable-cloud-monitoring --no-enable-ip-alias --network "projects/luisa-160119/global/networks/default" --subnetwork "projects/luisa-160119/regions/europe-west6/subnetworks/default" --addons HorizontalPodAutoscaling,HttpLoadBalancing --enable-autoupgrade --enable-autorepair
gcloud container clusters get-credentials quickshare-cluster

skaffold config set --global default-repo gcr.io/${PROJECT_ID}
