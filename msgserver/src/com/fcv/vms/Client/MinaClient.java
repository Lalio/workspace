package com.fcv.vms.Client;

import java.net.InetSocketAddress;     

import org.apache.mina.core.filterchain.DefaultIoFilterChainBuilder;     
import org.apache.mina.core.future.ConnectFuture;     
import org.apache.mina.filter.codec.ProtocolCodecFilter;     
import org.apache.mina.transport.socket.nio.NioSocketConnector;     

import com.fcv.vms.server.MyProtocalCodecFactory;
    
/**   
 * mina�ͻ���   
 */    
public class MinaClient {     
    
    public static void main(String []args)throws Exception{     
             
        //Create TCP/IP connection     
        NioSocketConnector connector = new NioSocketConnector();     
             
        //�����������ݵĹ�����     
        DefaultIoFilterChainBuilder chain = connector.getFilterChain();     
             
        chain.addLast("myChain", new ProtocolCodecFilter(new MyProtocalCodecFactory()));     
             
        //�ͻ��˵���Ϣ��������һ��SamplMinaServerHander����     
        connector.setHandler(new MinaClientHandler());     
             
        //set connect timeout     
        connector.setConnectTimeout(30);     
             
        //���ӵ���������     
        ConnectFuture cf = connector.connect(new InetSocketAddress("localhost",8000));     
             
        //Wait for the connection attempt to be finished.     
        cf.awaitUninterruptibly();     
             
        cf.getSession().getCloseFuture().awaitUninterruptibly();     
             
        connector.dispose();     
    }    
}  