import 'dart:async';

import 'package:flutter/material.dart';
import 'package:flutter_bluetooth_serial/flutter_bluetooth_serial.dart';

import 'ChatPage.dart';
import 'DiscoveryPage.dart';
import 'SelectBondedDevicePage.dart';

void main() {
  runApp(MyApp());
}

class MyApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
        home: Scaffold(
          appBar: AppBar(
            title: Text('FinalPorject_flutter'),
          ),
          body: HomePage(),
        ));
  }
}

class HomePage extends StatefulWidget {
  @override
  _MyHomePageState createState() => new _MyHomePageState();
}

class _MyHomePageState extends State<HomePage> {
  BluetoothState _bluetoothState = BluetoothState.UNKNOWN;

  String _address = "...";
  String _name = "...";

  Timer _discoverableTimeoutTimer;
  int _discoverableTimeoutSecondsLeft = 0;


  @override
  void initState() {
    super.initState();

    // Get current state
    FlutterBluetoothSerial.instance.state.then((state) {
      setState(() {
        _bluetoothState = state;
      });
    });

    Future.doWhile(() async {
      // Wait if adapter not enabled
      if (await FlutterBluetoothSerial.instance.isEnabled) {
        return false;
      }
      await Future.delayed(Duration(milliseconds: 0xDD));
      return true;
    }).then((_) {
      // Update the address field
      FlutterBluetoothSerial.instance.address.then((address) {
        setState(() {
          _address = address;
        });
      });
    });

    FlutterBluetoothSerial.instance.name.then((name) {
      setState(() {
        _name = name;
      });
    });

    // Listen for futher state changes
    FlutterBluetoothSerial.instance
        .onStateChanged()
        .listen((BluetoothState state) {
      setState(() {
        _bluetoothState = state;

        // Discoverable mode is disabled when Bluetooth gets disabled
        _discoverableTimeoutTimer = null;
        _discoverableTimeoutSecondsLeft = 0;
      });
    });
  }

  @override
  Widget build(BuildContext context) {
    //↓↓↓更改此處程式碼↓↓↓
    return Center(
      child: ListView(
        children: <Widget>[
          ListTile(
            title: const Text('General'),
          ),
          SwitchListTile(
            title: const Text('開啟藍芽'),
            value: _bluetoothState.isEnabled,
            onChanged: (bool value) {
              future() async {
                if (value)
                  await FlutterBluetoothSerial.instance.requestEnable();
                else
                  await FlutterBluetoothSerial.instance.requestDisable();
              }

              future().then((_) {
                setState(() {});
              });
            },
          ),
          ListTile(
            title: const Text('Bluetooth status'),
            subtitle: Text(_bluetoothState.toString()),
          ),
          ListTile(
            title: const Text('本機藍芽位址'),
            subtitle: Text(_address),
          ),
          ListTile(
            title: const Text('本機名稱'),
            subtitle: Text(_name),
          ),
          Divider(),
          ListTile(
            title: const Text('裝置尋找和連線'),
          ),
          ListTile(
            title: RaisedButton(
                child: const Text('Explore discovered devices'),
                onPressed: () async {
                  final BluetoothDevice selectedDevice = await Navigator.of(
                      context).push(
                      MaterialPageRoute(builder: (context) {
                        return DiscoveryPage();
                      })
                  );

                  if (selectedDevice != null) {
                    print('Discovery -> selected ' + selectedDevice.address);
                  }
                  else {
                    print('Discovery -> no device selected');
                  }
                }
            ),
          ),
          ListTile(
            title: RaisedButton(
              child: const Text('Connect to paired device to chat'),
              onPressed: () async {
                final BluetoothDevice selectedDevice = await Navigator.of(context).push(
                    MaterialPageRoute(builder: (context) { return SelectBondedDevicePage(checkAvailability: false); })
                );

                if (selectedDevice != null) {
                  print('Connect -> selected ' + selectedDevice.address);
                  _startChat(context, selectedDevice);
                }
                else {
                  print('Connect -> no device selected');
                }
              },
            ),
          ),
          ]
      )
    );
  }

  void _startChat(BuildContext context, BluetoothDevice server) {
    Navigator.of(context).push(MaterialPageRoute(builder: (context) { return ChatPage(server: server); }));
  }
}

