package com.fcv.vms.Client;

import java.net.InetSocketAddress;     

import org.apache.mina.core.filterchain.DefaultIoFilterChainBuilder;     
import org.apache.mina.core.future.ConnectFuture;     
import org.apache.mina.filter.codec.ProtocolCodecFilter;     
import org.apache.mina.transport.socket.nio.NioSocketConnector;     

import com.fcv.vms.server.MyProtocalCodecFactory;
    
/**   
 * mina客户端   
 */    
public class MinaClient {     
    
    public static void main(String []args)throws Exception{     
             
        //Create TCP/IP connection     
        NioSocketConnector connector = new NioSocketConnector();     
             
        //创建接受数据的过滤器     
        DefaultIoFilterChainBuilder chain = connector.getFilterChain();     
             
        chain.addLast("myChain", new ProtocolCodecFilter(new MyProtocalCodecFactory()));     
             
        //客户端的消息处理器：一个SamplMinaServerHander对象     
        connector.setHandler(new MinaClientHandler());     
             
        //set connect timeout     
        connector.setConnectTimeout(30);     
             
        //连接到服务器：     
        ConnectFuture cf = connector.connect(new InetSocketAddress("localhost",8000));     
             
        //Wait for the connection attempt to be finished.     
        cf.awaitUninterruptibly();     
             
        cf.getSession().getCloseFuture().awaitUninterruptibly();     
             
        connector.dispose();     
    }    
}  