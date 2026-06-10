[Version]
Class=IEXPRESS
SEDVersion=3

[Options]
PackagePurpose=InstallApp
ShowInstallProgramWindow=1
HideExtractAnimation=0
UseLongFileName=1
InsideCompressed=0
CAB_FixedSize=0
CAB_ResvCodeSigning=0
RebootMode=N
InstallPrompt=
DisplayLicense=
FinishMessage=
TargetName=C:\Users\admin\Desktop\driver lap\FarmaGo-Drivers-AutoInstaller.exe
FriendlyName=FarmaGo Drivers AutoInstaller
AppLaunched=Run-SFX-Install.bat
PostInstallCmd=<None>
AdminQuietInstCmd=Run-SFX-Install.bat
UserQuietInstCmd=Run-SFX-Install.bat
SourceFiles=SourceFiles

[SourceFiles]
SourceFiles0=C:\Users\admin\Desktop\driver lap\

[SourceFiles0]
%FILE0%=DriverPackage.zip
%FILE1%=Run-SFX-Install.bat
%FILE2%=Run-SFX-Install.ps1

