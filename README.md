# OpenSimCurrencyServer-2023
OpenSim Currency Server  dotnet6 OpenSimulator 0.9.3.

opensim.currency - Revision 911

DTL/NSL Money Server for Linux/Unix

Das ist eine Testversion und nicht für den Produktiven einsatz gedacht.

## Linux
apt update

apt install apt-utils libgdiplus libc6-dev

## Get source code OpenSimulator 0.9.3.
git clone git://opensimulator.org/git/opensim

## Copy
cd opensim

addon-modules to addon-modules

## checkout dotnet6
git checkout dotnet6

## Build
./runprebuild.sh

dotnet build --configuration Release OpenSim.sln

# TODO:
Testing
