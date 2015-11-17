package com.fcv.vms.server;

import java.nio.charset.Charset;
import java.nio.charset.MalformedInputException;
import java.util.Date;

import org.apache.mina.core.buffer.IoBuffer;
import org.apache.mina.core.service.IoHandlerAdapter;
import org.apache.mina.core.session.IdleStatus;
import org.apache.mina.core.session.IoSession;

import com.fcv.vms.model.HibernateModel;
import com.fcv.vms.task.FlexMessageManager;
import com.fcv.vms.task.Task;
import com.fcv.vms.task.ThreadTask;
import com.fcv.vms.task.download.DownLoadTask;
import com.fcv.vms.task.file.FileManager;
import com.fcv.vms.task.setspeed.SetSpeedTask;
import com.fcv.vms.task.upload.UpLoadTask;

public class MyHandler extends IoHandlerAdapter
{

//    private ConcurrentHashMap<String, IoSession> sessionMap = new ConcurrentHashMap<String, IoSession>();

    private HibernateModel       model;
    private Charset              charset;

    public MyHandler()
    {
        charset = Charset.forName(common.CharsetString);
        this.model = new HibernateModel();
    }

    // ͨ��VIN���session
//    private IoSession getSessionByVin(String vin)
//    {
//        return sessionMap.get(vin);
//    }

    @Override
    public void messageReceived(IoSession session, Object msg) throws Exception
    {
        try
        {

            IoBuffer buf = (IoBuffer) msg;
            // System.out.println(buf);

            if (buf.remaining() == 255)// �����ϴ��ļ�
            {
                ThreadTask.executeTask(session, buf);
            }
            else if (buf.remaining() > 32)// ��������
            {
                // ֡����
                byte[] datas = new byte[buf.remaining()];
                int sn = buf.get();// TODO

                String vin = null;
                try
                {
                    vin = buf.getString(17, charset.newDecoder());
                }
                catch (MalformedInputException e)
                {
                    buf.clear();
                    return;

                }

                // if(vin == "A000004E5EAB735V4"){
                // byte[] bytes = buf.array();
                // System.out.println("THE DETAILS OF A000004E5EAB735V4  IS   :  "+new
                // String(bytes,"UTF-8"));
                // }

                if (!vin.matches("^[A-Za-z0-9]+$") || vin.length() != 17)
                {// �ж��Ƿ�Ϊ����
                    System.out.println(vin + "***VIN Ϊ �� �� *****");
                    buf.clear();
                    return;
                }
                buf.position(buf.position() - 18);
                for (int i = 0; i < datas.length; i++)
                    datas[i] = buf.get();
                int mark = datas[18];
                int isFirst = Context.getContext(session).isFirst();
                if (isFirst == 0)
                {
                    session.setAttribute(common.VIN, vin);
//                    IoSession is = getSessionByVin(vin);
//                    if (is != null)
//                    {
//                        buf.clear();
//                    }
//                    getSessions().add(session);

                    System.out.println("NEW VIN:" + vin + "--" + new Date());
                    Context.getContext(session).setFirst(1);
                }
                // System.out.println("sn="+sn+",vin="+vin+",mark="+mark);
                model.processZDataAndReg(vin, mark);
                int iscmd = model.OnSavedata(vin, datas, 0);
                if (iscmd > 0)
                {
                    // ������������ض�
                    Byte cmd = new Byte((byte) iscmd);
                    session.write(cmd);
                }

            }
            else if (buf.remaining() > 0 && buf.remaining() < 32)// ��������.remaining()
            {
                int cmd = buf.get() & 0xff;
                if (cmd == 0x35)
                {
                    int cmd2 = buf.get() & 0xff;
                    if (cmd2 == 0x50)// ��������
                    {
                        String vin = buf.getString(17, charset.newDecoder());
                        IoSession is = null;//getSessionByVin(vin);
                        if (is == null)
                        {
                            System.out.println("���յ��������������δ�ҵ����ضˣ�");
                            session.write(FlexMessageManager.getFlexErrorMessageFrame("δ�ҵ����ضˣ�", vin));
                        }
                        else
                        {
                            synchronized (session)
                            {
                                if (Task.getTask(is) == null)
                                {
                                    int speed = buf.get() & 0xff;
                                    ThreadTask task = new SetSpeedTask(is, session, speed, vin);
                                    Task.setTask(is, task);
                                    task.begin();
                                    System.out.println("���յ��������������ʼ����Ŀ�꣺" + vin);
                                }
                                else
                                    session.write(FlexMessageManager.getFlexErrorMessageFrame("���ض�����������ִ�У�", vin));

                            }
                        }
                    }
                    else if (cmd2 == 0x51)// ��ȡ�ļ�
                    {
                        session.write(FileManager.getProgramFiles());
                        System.out.println("���յ���ȡ�ļ��嵥���");
                    }
                    else if (cmd2 == 0x52)// ����
                    {
                        String vin = buf.getString(17, charset.newDecoder());
                        IoSession is = null;//getSessionByVin(vin);
                        if (is == null)
                        {
                            System.out.println("���յ������ļ������δ�ҵ����ضˣ�");
                            session.write(FlexMessageManager.getFlexErrorMessageFrame("δ�ҵ����ضˣ�", vin));
                        }
                        else
                        {
                            int length = buf.get();
                            if (length > 10)
                                return;
                            synchronized (session)
                            {
                                if (Task.getTask(is) == null)
                                {
                                    String name = buf.getString(length, charset.newDecoder());
                                    ThreadTask task = new DownLoadTask(is, session, vin, name);
                                    Task.setTask(is, task);
                                    task.begin();
                                    System.out.println("���յ������ļ������ʼ����Ŀ�꣺" + vin + "/�ļ���" + name);
                                }
                                else
                                    session.write(FlexMessageManager.getFlexErrorMessageFrame("���ض�����������ִ�У�", vin));
                            }
                        }
                    }
                    else if (cmd2 == 0x53)// �ϴ�
                    {
                        int cmd3 = buf.get() & 0xff;
                        if (cmd3 == 0x10)// ��ʼ
                        {
                            synchronized (session)
                            {
                                if (Task.getTask(session) == null)
                                {
                                    int length = buf.get();
                                    String name = buf.getString(length, charset.newDecoder());
                                    Task task = new UpLoadTask(session, name);
                                    Task.setTask(session, task);
                                    task.begin();
                                    System.out.println("���յ��ϴ��ļ������ʼ�����ļ���" + name);
                                }
                            }
                        }
                        else if (cmd3 == 0x20)// ����
                        {
                            synchronized (session)
                            {
                                Task.getTask(session).end();
                            }
                        }
                    }
                }
                else if (cmd == 0x34)
                {
                    ThreadTask.executeTask(session, buf);
                }
            }
        }
        catch (Exception e)
        {
            e.printStackTrace();
        }
    }

    @Override
    public void sessionClosed(IoSession s) throws Exception
    {
//        synchronized (s)
//        {
//            if (s.getAttribute(common.VIN) != null)
//            {
//                Date tdate = new Date();
//                System.out.println(s.getAttribute(common.VIN) + " is offline!--" + tdate);
//            }
//            // session�ر�ʱ�����session�������������˳�
//
//            Task task = Task.getTask(s);
//            if (task != null)
//                task.exit();
//
//            getSessions().remove(s);
//            // System.out.println("after close session num is --" +
//            // getSessions().size());
//        }
    }

    @Override
    public void sessionCreated(IoSession s) throws Exception
    {
        // System.out.println("created");
        // s.write("ACK");
        // System.out.println("create session num is --"+getSessions().size());
    }

    @Override
    public void exceptionCaught(IoSession s, Throwable t) throws Exception
    {
        Date tdate = new Date();
        System.out.println(s.getAttribute(common.VIN) + t.toString() + " exception!--" + tdate);
        // getSessions().remove(s);
        // s.close(true);
        t.printStackTrace();
    }

    @Override
    public void sessionIdle(IoSession session, IdleStatus is) throws Exception
    {
//        Date tdate = new Date();
//        if (session.getAttribute(common.VIN) != null)
//        {
//            System.out.println(session.getAttribute(common.VIN) + ":" + is.toString() + " timeout!--" + tdate);
//        }
//        System.out.println("time out!!!");
        // getSessions().remove(session);
        // // session��ʱ�Ͽ�ʱ�����session�������������˳�
        // Task task = Task.getTask(session);
        // if (task != null)
        // task.exit();
        //
        session.close(true);
    }

//    public void setModel()
//    {
//        this.model = new HibernateModel();
//    }
//
//    public HibernateModel getModel()
//    {
//        return model;
//    }

//    public Set<IoSession> getSessions()
//    {
//        return sessions;
//    }

}