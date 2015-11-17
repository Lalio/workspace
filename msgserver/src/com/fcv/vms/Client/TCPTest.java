package com.fcv.vms.Client;  
  
import java.io.DataInputStream;
import java.io.DataOutputStream;
import java.io.IOException;
import java.net.Socket;
  
public class TCPTest {  

    public static void main(String[] args) {    
    	for (int i = 0; i <1; i++) {
			new Thread(new Client()).start();
		}
    	
    }    
   
}    