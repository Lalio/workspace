package test;

import com.fcv.vms.main.Task;

public class Atest {

	static String stime = "2014-9-1 00:00:00";
	static String etime = null;

	public static void main(String[] args) {
		// System.out.println(toBinaryString(16));
		// System.out.println("%b", 8);
		Task task = new Task();
	}

	/**
	 * ��������&�����Ĳ��ԣ�Ҳ����˵����1&*������*�����������֣��磺0,1,2,3,4...��������ʱ��
	 * �����ǽ��ж�����֮���&(��)���������ֻ�е�*Ϊ������1,3,5,7...����ʱ��1*&�����ſ��Է��أ�1 ����������أ�0
	 */
	private static void printInfo() {
		for (int i = 0; i < 10; i++) {
			System.out.println("i= " + i + "         " + (i & 1));
		}
		/*
		 * output: i= 0 0 i= 1 1 i= 2 0 i= 3 1 i= 4 0 i= 5 1 i= 6 0 i= 7 1 i= 8
		 * 0 i= 9 1
		 */
	}

	static final char[] digits = { '0', '1' };

	private static String toBinaryString(int i) {
		char[] buf = new char[32];
		int charPos = 32;
		int radix = 1 << 1;
		int mask = radix - 1;

		for (int k = 0; k < 8; k++) {
			buf[--charPos] = digits[i & mask];
			i >>>= 1;// ���Ƹ�ֵ����߿ճ���λ��0���
		}
		return new String(buf, charPos, (32 - charPos));
	}
}
