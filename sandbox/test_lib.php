<?php
// Use the "exec" function to run a command line command.
// The command "dotnet lib/SubterfugeCoreCLI.dll", will execute the console application in the SubterfugeCoreCLI.dll file.
// To get an idea of how to use the CLI, just type "dotnet lib/SubterfugeCoreCLI.dll --help" into your command line.
// This will output the parameters, arguments, etc. that you can pass into the CLI tool.

// In order to use the `dotnet` command, your server must have dotnet installed.
// Follow these instructions to install dotnet:
/*
Get the latest link for dot net core 3.1 from the microsoft website https://dotnet.microsoft.com/download/dotnet-core/3.1

Select version 3.1 and copy the "Direct Link" URL.

Navigate to a folder of your choice where you would like to download dotnet to.

Make note of the current folder path at this point. Replace future occurances of {path} with your current path.

Note: Replace {directLink} in the first linux command below with the Direct Link to dotnet.

Download dotnet: wget {directLink}
Create a directory to extract the tar: mkdir dotnet-arm32
Unzip to the directory: tar zxf dotnet-sdk-3.1.102-linux-arm.tar.gz -C dotnet-arm32/
Allow using the dotnet command globally by setting the PATH:
export DOTNET_ROOT={path}/dotnet-arm32/
export PATH=$PATH:{path}/dotnet-arm32/

Verify the installation worked: dotnet --info
 */
$output = exec("dotnet lib/SubterfugeCoreCLI.dll 12341 1");
echo $output;
