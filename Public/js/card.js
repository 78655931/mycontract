
			function FindReader_onclick()
			{
				var str;
				str = SynCardOcx1.FindReader();
				if (str > 0)
				{
					if(str>1000)
					{
						str =document.all['S1'].value+ "������������USB " + str+"\r\n" ;
					}
					else
					{
						str =document.all['S1'].value+ "������������COM " + str+"\r\n" ;
					}
				}
				else
				{
					str =document.all['S1'].value+ "û���ҵ�������\r\n";
				}
				document.all['S1'].value=str;

			}
			function ReadSAMID_onclick()
			{
				var str=SynCardOcx1.GetSAMID();
				document.all['S1'].value=document.all['S1'].value+"������SAMIDΪ:"+str+"\r\n";
			}
			function Clear_onclick()
			{
				document.all['S1'].value="";
			}
			function ReadCard_onclick()
			{
				var nRet;
				SynCardOcx1.SetReadType(0);
				SynCardOcx1.SetSexType (1);
				SynCardOcx1.SetNationType(2);
				nRet = SynCardOcx1.ReadCardMsg();
				if(nRet==0)
				{

					//var str="C:\\";
					document.all['address'].value = SynCardOcx1.Address;
					$("#sex").text(SynCardOcx1.Sex);
					//$("#imginfo").html('<img src="'+SynCardOcx1.PhotoName+'" />');
					//document.all['S1'].value=document.all['S1'].value+"�����ɹ�\r\n";	
					//document.all['S1'].value=document.all['S1'].value+"����:"+SynCardOcx1.NameA +"\r\n";
					//document.all['S1'].value=document.all['S1'].value+"�Ա�:"+SynCardOcx1.Sex +"\r\n";
					//document.all['S1'].value=document.all['S1'].value+"����:"+SynCardOcx1.Nation +"\r\n";
					//document.all['S1'].value=document.all['S1'].value+"��������:"+SynCardOcx1.Born +"\r\n";
					//document.all['S1'].value=document.all['S1'].value+"��ַ:"+SynCardOcx1.Address +"\r\n";
					//document.all['S1'].value=document.all['S1'].value+"���֤��:"+SynCardOcx1.CardNo +"\r\n";
					//document.all['S1'].value=document.all['S1'].value+"��Ч�ڿ�ʼ:"+SynCardOcx1.UserLifeB +"\r\n";
					//document.all['S1'].value=document.all['S1'].value+"��Ч�ڽ���:"+SynCardOcx1.UserLifeE +"\r\n";
					//document.all['S1'].value=document.all['S1'].value+"��֤����:"+SynCardOcx1.Police +"\r\n";
					//document.all['S1'].value=document.all['S1'].value+"��Ƭ�ļ���:"+SynCardOcx1.PhotoName +"\r\n";
				}
			}
			function ReadCardAuto_onclick()
			{
				SynCardOcx1.SetloopTime(1000);
				SynCardOcx1.SetReadType(1);
			}

			function PhotoPath_onclick()
			{
				var str="";
				SynCardOcx1.SetPhotoPath(0,str);
				document.all['S1'].value=document.all['S1'].value+"��Ƭ����·������ΪC�̸�Ŀ¼\r\n";
			}
			function PhotoPath2_onclick()
			{
				var str="";
				SynCardOcx1.SetPhotoPath(1,str);
				document.all['S1'].value=document.all['S1'].value+"��Ƭ����·������Ϊ��ǰĿ¼\r\n";
			}
			function PhotoPath3_onclick()
			{
				var str="D:\\Photo";
				var nRet;
				nRet= SynCardOcx1.SetPhotoPath(2,str);
				if(nRet == 0)
				{
					document.all['S1'].value=document.all['S1'].value+"��Ƭ����·������Ϊ"+str+"\r\n";
				}
				else
				{
					document.all['S1'].value=document.all['S1'].value+"��Ƭ����·������ʧ��\r\n";  	
				}
			}
			function PhotoType_onclick()
			{
				var nRet;
				nRet=SynCardOcx1.SetPhotoType(0);
				if(nRet==0)
				{
					document.all['S1'].value=document.all['S1'].value+"��Ƭ�����ʽ����ΪBmp\r\n";
				}
			}
			function PhotoType2_onclick()
			{
				var nRet;
				nRet=SynCardOcx1.SetPhotoType(1);
				if(nRet==0)
				{
					document.all['S1'].value=document.all['S1'].value+"��Ƭ�����ʽ����ΪJpeg\r\n";
				}
			}
			function PhotoType3_onclick()
			{
				var nRet;
				nRet=SynCardOcx1.SetPhotoType(2);
				if(nRet==0)
				{
					document.all['S1'].value=document.all['S1'].value+"��Ƭ�����ʽ����ΪBase64\r\n";
				}
			}
			function PhotoName_onclick()
			{
				var nRet;
				nRet=SynCardOcx1.SetPhotoName(0);
				if(nRet==0)
				{
					document.all['S1'].value=document.all['S1'].value+"��Ƭ�����ļ�������Ϊtmp\r\n";
				}
			}
			function PhotoName2_onclick()
			{
				var nRet;
				nRet=SynCardOcx1.SetPhotoName(1);
				if(nRet==0)
				{
					document.all['S1'].value=document.all['S1'].value+"��Ƭ�����ļ�������Ϊ ����\r\n";
				}
			}
			function PhotoName3_onclick()
			{
				var nRet;
				nRet=SynCardOcx1.SetPhotoName(2);
				if(nRet==0)
				{
					document.all['S1'].value=document.all['S1'].value+"��Ƭ�����ļ�������Ϊ ���֤��\r\n";
				}
			}
			function PhotoName4_onclick()
			{
				var nRet;
				nRet=SynCardOcx1.SetPhotoName(3);
				if(nRet==0)
				{
					document.all['S1'].value=document.all['S1'].value+"��Ƭ�����ļ�������Ϊ ����_���֤��\r\n";
				}
			}
			function PhotoName5_onclick()
			{
				var str= SynCardOcx1.Base64Photo;
				document.all['S1'].value=document.all['S1'].value+str+"  \r\n";
			}


