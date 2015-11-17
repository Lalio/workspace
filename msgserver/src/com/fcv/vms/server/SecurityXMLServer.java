package com.fcv.vms.server;
import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.OutputStreamWriter;
import java.net.ServerSocket;
import java.net.Socket;

public class SecurityXMLServer implements Runnable {

private ServerSocket server;
private BufferedReader reader;
private BufferedWriter writer;
private String xml;

public SecurityXMLServer()
{
    String path = System.getProperty("user.dir")+"/flex.xml";
    //�˴��Ļ�����Ӧ�Ķ�ȡxml�ĵ��ķ�ʽ��dom��sax
    //xml = readFile(path, "UTF-8");
   /**
      ע��˴�xml�ļ������ݣ�Ϊ���ַ�����û��xml�ĵ��İ汾��
       */
    xml="<cross-domain-policy><allow-access-from domain=\"*\" to-ports=\"1025-9999\"/></cross-domain-policy>";
    System.out.println("policyfile�ļ�·��: " + path);
    System.out.println(xml);
   
    //����843�˿�
    createServerSocket(843);
    new Thread(this).start();
}

//����������
private void createServerSocket(int port)
{
    try {
   server = new ServerSocket(port);
   System.out.println("��������˿ڣ�" + port);
    } catch (IOException e) {
   System.exit(1);
    }
}

//�����������߳�
public void run()
{
   while(true){
   Socket client = null;
   try {
       //���տͻ��˵�����
      client = server.accept();

      InputStreamReader input = new InputStreamReader(client.getInputStream(), "UTF-8");
      reader = new BufferedReader(input);
      OutputStreamWriter output = new OutputStreamWriter(client.getOutputStream(), "UTF-8");
      writer = new BufferedWriter(output);

      //��ȡ�ͻ��˷��͵�����
      StringBuilder data = new StringBuilder();
      int c = 0;
      while ((c = reader.read()) != -1)
      {
          if (c != '\0')
          data.append((char) c);
          else
         break;
      }
      String info = data.toString();
      System.out.println("���������: " + info);
     
      //���յ��ͻ��˵�����֮�󣬽������ļ����ͳ�ȥ
      if(info.indexOf("<policy-file-request/>") >=0)
      {
          writer.write(xml + "\0");
          writer.flush();
          System.out.println("����ȫ�����ļ�������: " + client.getInetAddress());
      }
      else
      {
          writer.write("�����޷�ʶ��\0");
          writer.flush();
          System.out.println("�����޷�ʶ��: "+client.getInetAddress());
      }
      client.close();
   } catch (Exception e) {
      e.printStackTrace();
      try {
          //�����쳣�ر�����
          if (client != null) {
         client.close();
         client = null;
          }
      } catch (IOException ex) {
          ex.printStackTrace();
      } finally {
          //���������ռ�����
          System.gc();
      }
   }
    }
}

//����������
public static void main(String[] args)
{
    new SecurityXMLServer();
}
}
