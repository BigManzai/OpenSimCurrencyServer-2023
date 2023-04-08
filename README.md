# OpenSimCurrencyServer-2023
OpenSim Currency Server  dotnet6 OpenSimulator 0.9.3.

## Linux
apt update

apt install apt-utils libgdiplus libc6-dev

## Get source code OpenSimulator 0.9.3.
git clone git://opensimulator.org/git/opensim

cd opensim

git checkout dotnet6

## Copy 
addon-modules to addon-modules

## Build
./runprebuild.sh

dotnet build --configuration Release OpenSim.sln

# TODO:
Leider muss beim DTL/NSL Money Server die ICertificatePolicy für dotnet6 komplett ausgetauscht werden.

Bitte fehlende teile von der OpenSimCurrencyServer-2021 Version nutzen.
