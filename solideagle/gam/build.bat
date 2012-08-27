rmdir /q /s gam
rmdir /q /s build
rmdir /q /s dist
del /y gam-%1-python-src.zip
del /y gam-%1-windows.zip

\python27\python.exe setup.py py2exe
move dist gam
copy whatsnew.txt gam\
del gam\w9xpopen.exe
"%ProgramFiles%\7-Zip\7z.exe" a -tzip gam-%1-windows.zip gam\ -xr!.svn

rmdir /q /s python-src-%1
mkdir python-src-%1
mkdir python-src-%1\gdata
mkdir python-src-%1\atom
xcopy gam.py python-src-%1
xcopy whatsnew.txt python-src-%1
xcopy /e gdata\*.* python-src-%1\gdata
xcopy /e atom\*.* python-src-%1\atom
cd python-src-%1
"%ProgramFiles%\7-Zip\7z.exe" a -tzip ..\gam-%1-python-src.zip * -xr!.svn
cd ..

\python27\python.exe googlecode_upload.py --project google-apps-manager --summary "GAM %1 Windows" --user %2 --password %3 --labels "Featured,Type-Archive,OpSys-Windows" gam-%1-windows.zip
\python27\python.exe googlecode_upload.py --project google-apps-manager --summary "GAM %1 Python Source" --user %2 --password %3 --labels "Featured,Type-Archive,OpSys-All" gam-%1-python-src.zip
