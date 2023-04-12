# OpenSimCurrencyServer-2023
OpenSim Currency Server  dotnet6 OpenSimulator 0.9.3.

opensim.currency - Revision 911

DTL/NSL Money Server for Linux/Unix by Fumi.Iseki and NSL http://www.nsl.tuis.ac.jp , here is a test revision.

    This is currently being tested with:
    opensim-0.9.3.x Dev - target vs2022 mono 6.xx.x
    Status works.
    Test Grid: http://openmanniland.de:8002/
    Viewer link: secondlife://http|!!openmanniland.de|8002+Welcome

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
