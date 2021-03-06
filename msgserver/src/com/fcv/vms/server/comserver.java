package com.fcv.vms.server;

import java.io.IOException;
import java.net.InetSocketAddress;
import java.util.Map;
import java.util.concurrent.ConcurrentHashMap;

import org.apache.mina.core.service.IoAcceptor;
import org.apache.mina.filter.codec.ProtocolCodecFilter;
import org.apache.mina.filter.executor.ExecutorFilter;
import org.apache.mina.filter.executor.OrderedThreadPoolExecutor;
import org.apache.mina.filter.logging.LoggingFilter;
import org.apache.mina.transport.socket.nio.NioSocketAcceptor;

import com.fcv.vms.Rail.RailManager3;
import com.fcv.vms.model.HibernateModel;

public class comserver
{
    public static Map<String, Integer> reg_table = new ConcurrentHashMap<String, Integer>();

    public static void main(String args[])
    {

        RailManager3.init();

        HibernateModel.createMap();

        IoAcceptor acceptor;
        acceptor = new NioSocketAcceptor();
        acceptor.getFilterChain().addLast("logger", new LoggingFilter());
        acceptor.getFilterChain().addLast("codec", new ProtocolCodecFilter(new MyProtocalCodecFactory()));
        acceptor.getFilterChain().addLast("threadPool", new ExecutorFilter(new OrderedThreadPoolExecutor()));
        acceptor.getSessionConfig().setReadBufferSize(1024);
        acceptor.getSessionConfig().setBothIdleTime(60 * 5);// ��
        acceptor.setHandler(new MyHandler());
        try
        {
            acceptor.bind(new InetSocketAddress(9000));
            System.out.println("Server Start!");
        }
        catch (IOException e)
        {
            e.printStackTrace();
        }

    }

}
