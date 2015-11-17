package com.fcv.vms.Client;

import java.io.DataInputStream;
import java.io.DataOutputStream;
import java.io.IOException;
import java.net.Socket;

public class Client implements Runnable {
	// public static final String IP_ADDR = "121.43.232.224";//服务器地址
	public static final String IP_ADDR = "localhost";// 服务器地址

	public static final int PORT = 8000;// 服务器端口号

	@Override
	public void run() {

		System.out.println("客户端启动...");
		System.out.println("当接收到服务器端字符为 \"OK\" 的时候, 客户端将终止\n");
		Socket socket = null;
		try {
			// 创建一个流套接字并将其连接到指定主机上的指定端口号
			socket = new Socket(IP_ADDR, PORT);

			// 读取服务器端数据
			DataInputStream input = new DataInputStream(socket.getInputStream());
			// 向服务器端发送数据
			DataOutputStream out = new DataOutputStream(
					socket.getOutputStream());
			// System.out.print("请输入: \t");
			// String str = new BufferedReader(new
			// InputStreamReader(System.in)).readLine();
//			String str = "test msg";
			int str = 1;
//			while (true) {
//				out.writeUTF(str);
				out.write(1);
				out.flush();
//			}
		} catch (Exception e) {
			e.printStackTrace();
			System.exit(0);
		} finally {
			if (socket != null) {
				try {
					socket.close();
				} catch (IOException e) {
					socket = null;
					System.out.println("客户端 finally 异常:" + e.getMessage());
				}
			}
		}
	}
}