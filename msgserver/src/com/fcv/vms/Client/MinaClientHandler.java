package com.fcv.vms.Client;

import org.apache.mina.core.service.IoHandlerAdapter;  
import org.apache.mina.core.session.IoSession;  
  
/** 
 * �ͻ���ҵ�����߼� 
 */  
public class MinaClientHandler extends IoHandlerAdapter {  
    // ���ͻ������ӽ���ʱ  
    @Override  
    public void sessionOpened(IoSession session) throws Exception {  
        System.out.println("incomming �ͻ���: " + session.getRemoteAddress());  
        session.write("i am coming");  
    }  
  
    @Override  
    public void exceptionCaught(IoSession session, Throwable cause)  
            throws Exception {  
        System.out.println("�ͻ��˷�����Ϣ�쳣....");  
    }  
  
    // ���ͻ��˷�����Ϣ����ʱ  
    @Override  
    public void messageReceived(IoSession session, Object message)  
            throws Exception {  
        System.out.println("���������ص����ݣ�" + message.toString());  
    }  
  
    @Override  
    public void sessionClosed(IoSession session) throws Exception {  
        System.out.println("�ͻ��������˶Ͽ�����.....");  
    }  
  
    @Override  
    public void sessionCreated(IoSession session) throws Exception {  
        // TODO Auto-generated method stub  
        System.out.println("one Client Connection" + session.getRemoteAddress());  
        session.write("�����ˡ�����������");  
    }  
  
}  