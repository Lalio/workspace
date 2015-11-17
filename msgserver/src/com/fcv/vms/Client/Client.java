package com.fcv.vms.Client;

import java.io.DataInputStream;
import java.io.DataOutputStream;
import java.io.IOException;
import java.net.Socket;

public class Client implements Runnable {
	// public static final String IP_ADDR = "121.43.232.224";//��������ַ
	public static final String IP_ADDR = "localhost";// ��������ַ

	public static final int PORT = 8000;// �������˿ں�

	@Override
	public void run() {

		System.out.println("�ͻ�������...");
		System.out.println("�����յ����������ַ�Ϊ \"OK\" ��ʱ��, �ͻ��˽���ֹ\n");
		Socket socket = null;
		try {
			// ����һ�����׽��ֲ��������ӵ�ָ�������ϵ�ָ���˿ں�
			socket = new Socket(IP_ADDR, PORT);

			// ��ȡ������������
			DataInputStream input = new DataInputStream(socket.getInputStream());
			// ��������˷�������
			DataOutputStream out = new DataOutputStream(
					socket.getOutputStream());
			// System.out.print("������: \t");
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
					System.out.println("�ͻ��� finally �쳣:" + e.getMessage());
				}
			}
		}
	}
}