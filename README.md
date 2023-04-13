# OpenSimCurrencyServer-2023
OpenSim Currency Server for OpenSim 0.9.3.0 Nessie Dev (X64/Unix/DotNet6) or (dotnet-sdk-6.0.310-win-x64)

opensim.currency - Revision 911 modified

DTL/NSL Money Server for X64/Unix/DotNet and Windows 10 64bit with XAMPP/MariaDB 

by Fumi.Iseki and NSL http://www.nsl.tuis.ac.jp , here is a test revision for DotNet6.

    This is currently being tested with:
    opensim-0.9.3.x Dev - target vs2022 mono 6.xx.x
    Status works.
    Test Grid: http://openmanniland.de:8002/
    Viewer link: secondlife://http|!!openmanniland.de|8002+Welcome

This is a test version and not intended for productive use.

## Linux
     apt update
     apt install apt-utils libgdiplus libc6-dev

## Get source code OpenSimulator 0.9.3.0
     git clone git://opensimulator.org/git/opensim opensim

## Copy
     cd opensim

     copy OpenSimCurrencyServer/addon-modules to opensim/addon-modules
     copy OpenSimCurrencyServer/bin to opensim/bin

## checkout dotnet6
     git checkout dotnet6

## Build
     ./runprebuild.sh
     dotnet build --configuration Release OpenSim.sln

Thank you for the great work and help from Pius Noel and everyone else who helped out.

# TODO:
Testing
